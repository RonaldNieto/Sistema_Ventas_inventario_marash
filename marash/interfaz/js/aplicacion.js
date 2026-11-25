document.querySelector('#togglePass')?.addEventListener('click',()=>{const p=document.querySelector('#password');p.type=p.type==='password'?'text':'password'});
document.querySelector('#menuBtn')?.addEventListener('click',()=>document.querySelector('.sidebar')?.classList.toggle('open'));
document.querySelectorAll('[data-confirm]').forEach(b=>b.addEventListener('click',e=>{if(!confirm(b.dataset.confirm))e.preventDefault()}));
const lines=document.querySelector('#sale-lines');const tpl=document.querySelector('#sale-template');
document.querySelector('#add-line')?.addEventListener('click',()=>{if(lines&&tpl)lines.append(tpl.content.cloneNode(true))});
lines?.addEventListener('click',e=>{if(e.target.matches('.remove-line')&&lines.querySelectorAll('.sale-row').length>1)e.target.closest('.sale-row').remove()});
function recalc(){let total=0;document.querySelectorAll('.sale-row').forEach(row=>{const s=row.querySelector('select'),q=row.querySelector('input');total+=(Number(s?.selectedOptions[0]?.dataset.price)||0)*(Number(q?.value)||0)});const el=document.querySelector('#sale-total');if(el)el.textContent=new Intl.NumberFormat('es-CO',{style:'currency',currency:'COP',maximumFractionDigits:0}).format(total)}
lines?.addEventListener('input',recalc);lines?.addEventListener('change',recalc);recalc();
