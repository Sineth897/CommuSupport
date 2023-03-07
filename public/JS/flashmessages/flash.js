 class Flash {
     constructor() {

     }

     static activeMessages = [];
     static activeElements = 0;
     static show(obj) {
         for(let key in obj) {
             this.showMessage(obj[key]);
         }
     }

     static showMessage(msg)    {

         const msgDiv = document.createElement('div');
            msgDiv.classList.add('flash-messageIn');
            msgDiv.innerHTML = '<p>' + msg['value'] + '</p>';
            msgDiv.style.setProperty('--flash-top',this.getPostion() + 'px');
            if(msg['type'] === 'success') {
                msgDiv.classList.add('success');
            }
            else if(msg['type'] === 'error') {
                msgDiv.classList.add('error');
            }

            document.body.appendChild(msgDiv);
            this.activeMessages.push(msgDiv);
            this.activeElements++;
            const {top,height} = msgDiv.getBoundingClientRect();

            setTimeout(() => this.removeMessage(msgDiv),5000);


     }

     static removeMessage(msgDiv) {
         msgDiv.classList.remove('flash-messageIn');
         msgDiv.classList.add('flash-messageOut');
         let h = msgDiv.getBoundingClientRect().height;
         let index = this.activeMessages.indexOf(msgDiv);
         this.activeMessages.splice(index,1);
         msgDiv.remove();
         this.activeElements--;
         //this.moveUp();
         for(let i = 0; i < this.activeMessages.length; i++) {
             this.activeMessages[i].style.setProperty('--flash-top', this.activeMessages[i].getBoundingClientRect().top - h -  16 + 'px');
             //this.activeMessages[i].classList.add('flash-messageUp');
         }

        let {top,height} = this.activeMessages[this.activeMessages.length - 1].getBoundingClientRect();

     }

     static getPostion() {
         if(this.activeElements === 0) {
             return 16;
         }
         else {
             let {bottom} = this.activeMessages[this.activeMessages.length - 1].getBoundingClientRect();
             return bottom + 16;
         }
     }
 }

 Flash.show(flashMsgs);

 let countDown = setInterval(function () {
     Flash.showMessage({
         type: 'success',
         value: 'Test Flash Message'
     })
 }, 1500);