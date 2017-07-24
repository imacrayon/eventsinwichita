// Last scroller position.
let scrollerPosition = 0

let lastScrollerPosition = 0

// Flag for the DOM performing updates.
let ticking = false

// Update the DOM by fading in elements as they come into view.
const update = function () {
  var elements = document.querySelectorAll('.sticky');
  Array.prototype.forEach.call(elements, el => {
    const position = window.getComputedStyle(el).position
    if (position === '-webkit-sticky' || position === 'sticky') return

    if (scrollerPosition >= el.offsetTop && !el.classList.contains('active')) {
      el.style.height = el.querySelectorAll('.sticky-fix')[0].offsetHeight + 'px'
      el.classList.add('active')
    }

    if (scrollerPosition < el.offsetTop && el.classList.contains('active')) {
      el.classList.remove('active')
    }
  })

  ticking = false
}

// If the DOM is not already updating fire the update.
const requestTick = function () {
  if (!ticking) {
    requestAnimationFrame(update)
    ticking = true
  }
}

// Get the scroll position and request an update.
const onScroll = () => {
  lastScrollerPosition = scrollerPosition
  scrollerPosition = window.pageYOffset
  requestTick()
}

window.addEventListener('scroll', onScroll)
window.addEventListener('resize', onScroll)
