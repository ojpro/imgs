const ClickOutSide = {
    beforeMount:(el,binding)=>{
        el.ClickOutSideEvent =  event =>{
            if(!(el == event.target || el.contains(event.target))){
                binding.value()
            }
        }
        document.addEventListener('click',el.ClickOutSideEvent);
    },
    unmounted:el=> {
        document.removeEventListener('click',el.ClickOutSideEvent);
    },
}
export default ClickOutSide