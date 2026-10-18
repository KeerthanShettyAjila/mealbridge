document.querySelector('.menu-toggle')?.addEventListener('click',function(){const open=this.getAttribute('aria-expanded')!=='true';this.setAttribute('aria-expanded',String(open));document.querySelector('#navigation').classList.toggle('open',open);});
document.querySelectorAll('[data-confirm]').forEach(button=>button.addEventListener('click',event=>{if(!confirm(button.dataset.confirm))event.preventDefault();}));
const confirmation=document.querySelector('[name="confirm_password"]');
if(confirmation){const password=document.querySelector('[name="password"]');const validate=()=>confirmation.setCustomValidity(confirmation.value!==password.value?'Passwords must match.':'');confirmation.addEventListener('input',validate);password.addEventListener('input',validate);}
