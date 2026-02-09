import { addCompareEventListener, displayCompare} from './library';
window.onload = () => {

    let mainContent = document.querySelector('.compare_container')

    let compare = JSON.parse(mainContent?.dataset?.compare || false)

    addCompareEventListener()

    displayCompare(compare)
}
