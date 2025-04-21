import React from 'react';

const popup = () => {
  function exit_popup(i){
    //var popup = document.getElementById("connection_popup");
    //popup.classList.toggle("hide");
    let popup = concat("popup", i);
    let popup_text = concat("popup-text", i);

    document.getElementById(popup).style.visibility = 'hidden';
    document.getElementById(popup_text).style.visibility = 'hidden';
  }
  return(
    <div class="exit_popup" onClick={() => exit_popup()}></div>
  );
}

export default popup;
/*
function open_popup(i){
  //var popup = document.getElementById("connection_popup");
  //popup.classList.toggle("show");
  let popup = concat("popup", i);
  let popup_text = concat("popup-text", i);

  document.getElementById(popup).style.visibility = 'visible';
  document.getElementById(popup_text).style.visibility = 'visible';
}
*/
