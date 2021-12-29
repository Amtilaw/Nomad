/* eslint-disable vars-on-top */
/* eslint-disable comma-dangle */
/* eslint-disable linebreak-style */
/* eslint-disable no-undef */
/* eslint-disable prefer-const */
/* eslint-disable spaced-comment */
/* eslint-disable eqeqeq */
/* eslint-disable no-plusplus */
/* eslint-disable dot-notation */
/* eslint-disable quotes */
/* eslint-disable func-names */
/* eslint-disable prefer-arrow-callback */
/* eslint-disable object-shorthand */
/* eslint-disable no-mixed-operators */
/* eslint-disable no-var */
/* eslint-disable prefer-template */
/* eslint-disable consistent-return */
/* eslint-disable indent */
/* eslint-disable no-console */
/* eslint-disable no-unused-vars */
/* eslint-disable no-use-before-define */
let countQuestion = 0;
let countPallier = 1;
//compteur du prochain pallier
let count = 0;
let player = new Playerjs({ id: "player", file: filePath });
let quizzDone = true;
let questionValidate = true;
// Cette variable stocke le temp de la vidéo avant n-1 checkPalier()
let temp = player.api("time");
let seconde = 0;
let typeProposition;
console.log(typeProposition);
let questionsSize = Object.keys(json).length;

for (let i = 0; i < questionsSize; i++) {
  if (i > 0 && json[i]["timecode"] != json[i - 1]["timecode"]) {
    countPallier++;
  }
}

renderContextualMenue();
player.api("play");
checkPalier();
function PlayerjsEvents(event, id, data) {
  if (event == "play" && quizzDone == false) {
    player.api("pause");
  }
}

// Check si la vidéo à attein un pallier
function checkPalier() {
seconde++;
  isUserForwardVideo();
  if (
    player.api("time") > json[countQuestion]["timecode"] &&
    quizzDone == true
  ) {
    questionValidate = false;
    renderQuizz();
    showModal();
    player.api("pause");
    console.log(player.api("time"));
    console.log("Pallier " + count + " !");
    quizzDone = false;
    //quizzIsDone();
    count++;

    if (countPallier <= count) return 0;
  }

  renderMinuteur();
  setTimeout(() => {
    checkPalier();
  }, 1000);
}

function renderMinuteur() {
  var minutes = Math.floor(seconde / 60);
  var seconds = seconde - minutes * 60;
  $("#minuteur").html(minutes + ":" + seconds);
}

function showModal() {
  $("#main_right_panel_quizz").modal("show");
}

function sendUserRespond(userRes) {
  const url = controllerUserRespondPath;
  console.log(userRes);
  let answerId = [];
  for (
    let i = 0;
    i < Object.keys(json[countQuestion]["propositions"]).length;
    i++
  ) {
    answerId.push(json[countQuestion]["propositions"][i]["id"]);
  }
  $.ajax({
    type: "post",
    url: url,
    data: {
      question_id: json[countQuestion]["id"],
      user: "Jeanjean",
      answer: userRes,
      answerId: answerId,
      realAnswer: json[countQuestion]["propositions"],
    },
  })
    .done(function (data) {
      console.log(data);
    })
    .fail(function () {
      console.log("fail");
    });
}

function enregistreRespond(input) {
  const url = controllerenregistreRespondPath;
  let answerId = [];

  $.ajax({
    type: "post",
    url: url,
    data: {
      question_id: json[countQuestion]["id"],
      user: "Jeanjean",
      reponseDonne: input,
      answerId: answerId,
    },
  })
    .done(function (data) {
      console.log(data);
    })
    .fail(function () {
      console.log("fail");
    });
}

function enregistreChekRespond(checkbox) {
  const url = controllerenregistreChekRespondPath;
  let answerId = [];
  console.log(checkbox["value"]);
  
  $.ajax({
    type: "post",
    url: url,
    data: {
      question_id: json[countQuestion]["id"],
      user: "Jeanjean",
      reponseDonne: checkbox["value"],
      answerId: answerId,
    },
  })
    .done(function (data) {
      console.log(data);
    })
    .fail(function () {
      console.log("fail");
    });
}

function enregistreRadioRespond(radio) {
  const url = controllerenregistreRadioRespondPath;
  let answerId = [];
  console.log(radio);
  console.log(radio["value"]);
  $.ajax({
    type: "post",
    url: url,
    data: {
      question_id: json[countQuestion]["id"],
      user: "Jeanjean",
      reponseDonne: radio["value"],
      answerId: answerId,
    },
  })
    .done(function (data) {
      console.log(data);
    })
    .fail(function () {
      console.log("fail");
    });
}

function nextQuestionRender() {
  let canGoToNextQuestion = validateQuestion();
  if (canGoToNextQuestion == true) {
    countQuestion++;
    if (
      countQuestion < Object.keys(json).length &&
      countQuestion > 0 &&
      json[countQuestion - 1]["timecode"] == json[countQuestion]["timecode"]
    ) {
      //if question.type == qcm then :
      renderQuizz();
    } else {
      quizzDone = true;
      $("#main_right_panel_quizz").modal("hide");
      player.api("play");
      secondes = 0;
    }
  }
}

function renderQuizz() {
  document.getElementById("boardProps").innerHTML = "";
  $("#titrePallier").html(json[countQuestion]["titrePallier"]);
  $("#titreQuestion").html(json[countQuestion]["libelle"]);


    if (getTypeQuestion() == "text") {
    document.getElementById(
      "boardProps"
    ).innerHTML += `<label for="exampleFormControlInput1" class="form-label">Votre réponse</label>
                    <input type="text" class="md-input label-fixed customizedInput" name="box" id="proposition1">`;
  }
  if (getTypeQuestion() == "chek") {
    typeProposition = "checkbox";
    let jsonLibelle;
    let jsonIdproposition;
    for (
      let i = 0;
      i < Object.keys(json[countQuestion]["propositions"]).length;
      i++
    ) {
      jsonLibelle =
        json[countQuestion]["propositions"][i]["libelle"] + "</span> </div>";
       jsonIdproposition = json[countQuestion]["propositions"][i]["id"];
      document.getElementById(
        "boardProps"
      ).innerHTML += `<input type="${typeProposition}" name="box" id="proposition${i}" value="${jsonIdproposition}">
    <label for="proposition${i}" class="box label${i}">
    <div class="course">
    <span class="circle"> </span> 
    <span class="subject" id="propositionInput${i}"> ${jsonLibelle} </label>`;
    }
  }
  if (getTypeQuestion() == "qcm") {
      typeProposition = "radio";
    let jsonLibelle;
     let jsonIdproposition;
    for (
      let i = 0;
      i < Object.keys(json[countQuestion]["propositions"]).length;
      i++
    ) {
      jsonLibelle = json[countQuestion]["propositions"][i]["libelle"] + "</span> </div>";
      jsonIdproposition = json[countQuestion]["propositions"][i]["id"];
      jsonproposition = json[countQuestion]["propositions"][i]["libelle"];

      document.getElementById(
        "boardProps"
      ).innerHTML += `<input type="${typeProposition}" name="box" id="proposition${i}" value="${jsonIdproposition}"value2="${jsonproposition}">
    <label for="proposition${i}" class="box label${i}">
    <div class="course">
    <span class="circle"> </span> 
    <span class="subject" id="propositionInput${i}"> ${jsonLibelle} </label>`;
    }
  } 
}

function validateQuestion() {
  // if (getTypeQuestion() == "qcm") {
  //   let radios = document.getElementsByName("box");
  //   let userRes = [];
  //   for (var i = 0, len = radios.length; i < len; i++) {
  //     if (radios[i].checked) {
  //       userRes.push(1);
  //      console.log(userRes);
  //       questionValidate = true;
  //     }
  //     userRes.push(0);
  //   }

  //   sendUserRespond(userRes);

  //   if (questionValidate == true) return true;
  // } 
  if (getTypeQuestion() == "text") {
    let input = document.getElementById("proposition1").value;
    enregistreRespond(input);
    questionValidate = true;
    if (questionValidate == true) return true;
  }
  if (getTypeQuestion() == "chek") {
    let checkbox = document.getElementsByName("box");
    for (var i = 0, len = checkbox.length; i < len; i++) {
      if (checkbox[i].checked) {
        reponseDonne = checkbox[i];
        enregistreChekRespond(reponseDonne);
      }
    }
    
    questionValidate = true;
    if (questionValidate == true) return true;
  }
  if (getTypeQuestion() == "qcm") {
    let radio= document.getElementsByName("box");
    for (var i = 0, len = radio.length; i < len; i++) {
      console.log(radio[i].checked)
      if (radio[i].checked) {
        reponseDonne = radio[i];
        enregistreRadioRespond(reponseDonne);
      }
    }
    
    questionValidate = true;
    if (questionValidate == true) return true;
  }
  return true;
}

// verifie si l'utilisateur a avancé dans la vidéo return true si oui
function isUserForwardVideo() {
  // renvoie true si l'utilisateur à avancé de plus de 1 secondes dans la vidéo
  if (player.api("time") - temp > 3) {
    console.log("Vous ne pouvez pas avancer dans la vidéo");
    player.api("seek", "" + temp);
    return true;
  } else {
    temp = player.api("time");
    return false;
  }
}

function renderContextualMenue() {
  console.log(questionsSize);
  for (let i = questionsSize - 1; i >= 0; i--) {
    if (
      (i < questionsSize - 1 &&
        json[i]["titrePallier"] != json[i + 1]["titrePallier"]) ||
      i == questionsSize - 1
    ) {
      let li = document.createElement("li");
      li.innerHTML = `
                <div class="md-list-addon-element">
                    <i class="md-list-addon-icon material-icons">schedule</i>
                </div>
                <div class="md-list-content">
                    <span class="md-list-heading">${json[i]["titrePallier"]}</span>
                    <span class="uk-text-small uk-text-muted">${json[i]["timecode"]}</span>
                </div>`;

      let ul = document.getElementById("ulMenue");
      ul.prepend(li);
    }
  }
}

function getTypeQuestion() {
  if (json[countQuestion]["type"] == 1) return "qcm";
  if (json[countQuestion]["type"] == 2) return "text";
  if (json[countQuestion]["type"] == 3) return "chek";
}
