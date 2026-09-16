(() => {
  const form = document.querySelector('[data-post-form]');
  if (!form || typeof Quill === 'undefined') return;
  const editor = form.querySelector('[data-quill-editor]');
  const hidden = form.querySelector('[data-body-html]');
  const quill = new Quill(editor, {
    theme: 'snow',
    formats: ['header', 'bold', 'italic', 'underline', 'list', 'link', 'blockquote'],
    modules: { toolbar: [[{header:[2,3,false]}], ['bold','italic','underline'], [{list:'ordered'},{list:'bullet'}], ['blockquote','link'], ['clean']] }
  });
  quill.root.setAttribute('aria-label', 'Cuerpo de la nota');
  form.querySelector('.ql-header')?.setAttribute('aria-label', 'Nivel de encabezado');
  form.querySelector('.ql-tooltip input')?.setAttribute('aria-label', 'URL del enlace');
  form.addEventListener('submit', () => { hidden.value = quill.getSemanticHTML(); });
  const source = form.querySelector('[data-slug-source]'), target = form.querySelector('[data-slug-target]'); let slugTouched = Boolean(target.value);
  target.addEventListener('input', () => { slugTouched = true; }); source.addEventListener('input', () => { if (!slugTouched) target.value = source.value.normalize('NFD').replace(/[\u0300-\u036f]/g,'').toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/^-|-$/g,''); });
})();
