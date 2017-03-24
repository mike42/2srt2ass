// Attach color pickers using existing values.
$(function() {
  'use strict';

  // Some pre-defined colours for user selection
  var colorSelectors = {
    'black': '#000000',
    'white': '#ffffff',
    'red': '#FF0000',
    'default': '#777777',
    'primary': '#337ab7',
    'success': '#5cb85c',
    'info': '#5bc0de',
    'warning': '#f0ad4e',
    'danger': '#d9534f'
  };

  $('#main-color').colorpicker({
    format: 'hex',
    transparent: true,
    colorSelectors: colorSelectors
  }).on('changeColor', function(ev) {
    if(ev.value !== undefined) {
      $('@preview-box-main').css('color', ev.value);
    }
  });
  $('#alt-color').colorpicker({
    format: 'hex',
    transparent: true,
    colorSelectors: colorSelectors
  }).on('changeColor', function(ev) {
    if(ev.value !== undefined) {
      $('#preview-box-alt').css('color', ev.value);
    }
  });
  // Match the preview box colours and layour to what we have selected
  $('#preview-box-main').css('color', $('#main-color-input').val())
  $('#preview-box-alt').css('color', $('#alt-color-input').val());
  $('#alignTogetherBox').on('change', function() {
    $('#preview-box').addClass('preview-together');
  });
  $('#alignApartBox').on('change', function() {
    $('#preview-box').removeClass('preview-together');
  });
});
