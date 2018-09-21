const pv = 15;
let x = 10;
let left = false;
let right = false;
let count = 0;
let processing = false;
const mario = document.querySelector('.mario');
const image = mario.querySelector('img');

document.onkeydown = keydown;
document.onkeyup = keyup;

function keydown(event) {
  // left key
  if (processing) {
    return false;
  }
  processing = true;
  if (event.keyCode === 37) {
    left = true;
  }
  // right key
  if (event.keyCode === 39) {
    right = true;
  }
  display();
}

function keyup(event) {
  // left key
  if (event.keyCode === 37) {
    left = false;
    image.src = "/images/mario_4.png";
    count = 0;
  }
  // right key
  if (event.keyCode === 39) {
    right = false;
    image.src = "/images/mario_1.png";
    count = 0;
  }
  processing = false;
}

function display() {
  const fieldWidth = document.querySelector('.field').clientWidth;
  const marioWidth = mario.clientWidth;
  if (left === true) {
    if (( count % 2 ) === 0) {
      image.src = "/images/mario_6.png";
    } else {
      image.src = "/images/mario_5.png";
    }
    if (x > 10) {
      x = x - pv;
    }

  }
  if (right === true) {
    if (x < (fieldWidth - marioWidth)) {
      x = x + pv;
    }
    if (( count % 2 ) === 0) {
      image.src = "/images/mario_3.png";
    } else {
      image.src = "/images/mario_2.png";
    }
  }
  mario.style.left = x + 'px';
  count++;
  setTimeout(function() { processing = false; }, 100);
}
