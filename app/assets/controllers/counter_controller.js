import { Controller } from "@hotwired/stimulus"

export default class extends Controller {
   count = 0;
   static targets = ["count"];


   increment(){
      this.countTarget.innerHTML = ++this.count;
   }

   backToZero(){
      this.count = 0;
      this.countTarget.innerHTML = this.count;
   }

   decrement(){
      this.countTarget.innerHTML = --this.count;
   }


}