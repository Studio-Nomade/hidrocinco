(() => {
  const form = document.querySelector('[data-service-form]');
  if (!form) return;
  const editor = form.querySelector('[data-blocks-editor]');
  const hidden = form.querySelector('[data-content-json]');
  const typeSelect = form.querySelector('[data-new-block-type]');
  let blocks = [];
  try { blocks = JSON.parse(hidden.value || '[]'); } catch (_) { blocks = []; }

  const esc = (value = '') => String(value).replace(/[&<>"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));
  const lines = value => Array.isArray(value) ? value.join('\n') : '';
  const template = (block, index) => {
    const common = `<label>Título<input data-field="title" value="${esc(block.title)}"></label>`;
    let fields = '';
    if (block.type === 'intro') fields = `${common}<label>Párrafos, uno por línea<textarea rows="5" data-field="paragraphs">${esc(lines(block.paragraphs))}</textarea></label><label>Imagen del bloque<input type="file" name="block_image_${index}" accept="image/jpeg,image/png,image/webp"></label>${block.image ? `<small>Actual: ${esc(block.image)}</small>` : ''}`;
    if (block.type === 'feature') fields = `${common}<label>Párrafos, uno por línea<textarea rows="5" data-field="paragraphs">${esc(lines(block.paragraphs))}</textarea></label><label class="check-label"><input type="checkbox" data-field="decor" ${block.decor ? 'checked' : ''}> Mostrar gota decorativa</label>`;
    if (block.type === 'list') fields = `${common}<label>Introducción<textarea rows="3" data-field="intro">${esc(block.intro)}</textarea></label><label>Ítems, uno por línea<textarea rows="7" data-field="items">${esc(lines(block.items))}</textarea></label>`;
    if (block.type === 'richtext') fields = `<label>HTML permitido<textarea rows="10" data-field="html">${esc(block.html)}</textarea></label><small>Etiquetas permitidas: p, h2, h3, strong, em, listas, enlaces y citas.</small>`;
    return `<article class="content-block" data-index="${index}" data-type="${esc(block.type)}"><header><strong>Bloque ${index + 1}: ${esc(block.type)}</strong><div><button type="button" data-up aria-label="Subir bloque">↑</button><button type="button" data-down aria-label="Bajar bloque">↓</button><button type="button" data-remove>Eliminar</button></div></header>${fields}</article>`;
  };
  const render = () => { editor.innerHTML = blocks.map(template).join('') || '<p class="empty-editor">Aún no hay bloques.</p>'; };
  const sync = () => {
    editor.querySelectorAll('.content-block').forEach((element, index) => {
      const block = {...blocks[index]};
      element.querySelectorAll('[data-field]').forEach(input => {
        const field = input.dataset.field;
        if (field === 'paragraphs' || field === 'items') block[field] = input.value.split('\n').map(v => v.trim()).filter(Boolean);
        else if (input.type === 'checkbox') block[field] = input.checked;
        else block[field] = input.value;
      });
      blocks[index] = block;
    });
    hidden.value = JSON.stringify(blocks);
  };
  editor.addEventListener('click', event => {
    const button = event.target.closest('button'); if (!button) return;
    sync(); const item = button.closest('.content-block'); const index = Number(item.dataset.index);
    if (button.matches('[data-remove]')) blocks.splice(index, 1);
    if (button.matches('[data-up]') && index > 0) [blocks[index - 1], blocks[index]] = [blocks[index], blocks[index - 1]];
    if (button.matches('[data-down]') && index < blocks.length - 1) [blocks[index + 1], blocks[index]] = [blocks[index], blocks[index + 1]];
    render();
  });
  form.querySelector('[data-add-block]').addEventListener('click', () => { sync(); blocks.push({type:typeSelect.value,title:'',paragraphs:[],items:[]}); render(); });
  form.addEventListener('submit', sync);
  const source = form.querySelector('[data-slug-source]'), target = form.querySelector('[data-slug-target]'); let slugTouched = Boolean(target.value);
  target.addEventListener('input', () => { slugTouched = true; }); source.addEventListener('input', () => { if (!slugTouched) target.value = source.value.normalize('NFD').replace(/[\u0300-\u036f]/g,'').toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/^-|-$/g,''); });
  render();
})();
