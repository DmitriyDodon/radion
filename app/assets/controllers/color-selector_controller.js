import {Controller} from "@hotwired/stimulus"

export default class extends Controller {
    selected

    static targets = ["colorBox"];

    chooseColor(event) {
        this.colorBoxTarget.style.backgroundColor = window.getComputedStyle(event.currentTarget).backgroundColor;
    }
}