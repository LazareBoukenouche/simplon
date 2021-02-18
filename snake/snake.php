<?php

stream_set_blocking(STDIN, false);

$width = 60;
$height = 20;

$x= 30;
$y = 4;

$map = [];

for($i = 0; $i < $height; $i++) {
  for($j = 0; $j < $width; $j++) {
    $map[$i][$j] = " ";
  }
}
for($i = 0; $i < $width; $i++) {
  $map[0][$i] = getSpecialCharacter(2550);
  $map[$height - 1][$i] = getSpecialCharacter(2550);
}
for($i = 0; $i < $width; $i++) {
  $map[$i][0] = getSpecialCharacter(2551);
  $map[$i][$width - 1] = getSpecialCharacter(2551);
}
$map[0][0] = getSpecialCharacter(2554);
$map[0][$width - 1] = getSpecialCharacter(2557);
$map[$height - 1][0] = getSpecialCharacter("255a");
$map[$height - 1][$width - 1] = getSpecialCharacter("255d");

$previousChar = $map[$y][$x];

$direction = "RIGHT";

while(true) {
  fwrite(STDOUT, "\033[0;0f"); // move cursor to start

  $input = readInput();

  switch($input) {
    case "z":
      if ($direction !== "DOWN") {
        $direction = "UP";
      }
      break;
    case "q":
      if ($direction !== "RIGHT") {
        $direction = "LEFT";
      }
      break;
    case "d":
      if ($direction !== "LEFT") {
        $direction = "RIGHT";
      }
      break;
    case "s":
      if ($direction !== "UP") {
        $direction = "DOWN";
      }
      break;
  }

  $map[$y][$x] = $previousChar;
  switch($direction) {
    case "UP":
      if ($y === 1) {
        $y = $height - 1;
      } else {
        $y = $y - 1;
      }
      break;
    case "LEFT":
      if ($x === 1) {
        $x = $width - 1;
      } else {
        $x = $x - 1;
      }
      break;
    case "RIGHT":
      if ($x === $width - 2) {
        $x = 1;
      } else {
        $x = $x + 1;
      }
      break;
    case "DOWN":
      if ($y === $height - 2) {
        $y = 1;
      } else {
        $y = $y + 1;
      }
      break;
  }
  $previousChar = $map[$y][$x];
  $map[$y][$x] = "#";

  for($i = 0; $i < $height; $i++) {
    for($j = 0; $j < $width; $j++) {
        fwrite(STDOUT, $map[$i][$j]);
    }
    fwrite(STDOUT, PHP_EOL);
  }

  usleep(60000);
}

function getSpecialCharacter($code) {
  return html_entity_decode(sprintf('&#x%s;', $code), ENT_NOQUOTES, 'UTF-8');
}

function readInput() {
  readline_callback_handler_install('', function () {});
  $char = stream_get_contents(STDIN, 2);
  readline_callback_handler_remove();

  return $char;
}
