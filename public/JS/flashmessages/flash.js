 class Flash {
     static flashMsgDiv = document.querySelector('#flash-messages');
     static activeMessages = [];
     static activeElements = 0;
     static closeBtn = `<i class='material-icons flash-close'>close</i>`;

     constructor() {

     }

     static show(obj) {
         for(let key in obj) {
             this.showMessage(obj[key]);
         }
     }

     static showInit(flashMessages) {
         setTimeout(() => {
             if (flashMessages['success']) {
                 Flash.showMessage({type: 'success', value: flashMessages['success'].value});
             }
             if (flashMessages['error']) {
                 Flash.showMessage({type: 'error', value: flashMessages['error'].value});
             }
         }, 500);
     }

        static showMessage(msg,timeout = 8000) {
            const msgDiv = document.createElement('div');
            msgDiv.classList.add('flash-msg','flash-messageIn');
            msgDiv.innerHTML = '<p>' + msg['value'] + '</p>' + Flash.closeBtn;

            if(Flash.activeElements === 0) {
                Flash.flashMsgDiv.appendChild(msgDiv);
            }
            else {
                Flash.flashMsgDiv.insertBefore(msgDiv,Flash.flashMsgDiv.firstChild);
            }
            Flash.activeElements++;
            Flash.activeMessages.push(msgDiv);


            if(msg['type'] === 'success') {
                msgDiv.classList.add('successFlash');
            }
            else if(msg['type'] === 'error') {
                msgDiv.classList.add('errorFlash');
            }

            msgDiv.querySelector('.flash-close').addEventListener('click',function () {
                Flash.removeMessage(msgDiv);
            });

            setTimeout(() => msgDiv.classList.remove('flash-messageIn'),500);

            setTimeout(() => Flash.removeMessage(msgDiv),timeout);

        }

        static removeMessage(msgDiv) {
            msgDiv.classList.remove('flash-messageIn');
            msgDiv.classList.add('flash-messageOut');
            setTimeout(function () {
                msgDiv.classList.remove('flash-messageOut');
                msgDiv.remove();
                let index = Flash.activeMessages.indexOf(msgDiv);
                Flash.activeElements--;
                Flash.activeMessages = Flash.activeMessages.splice(index,1);
            },500);
        }

 }

 export default Flash;