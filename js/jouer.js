var generateWords = new Array();
var turnsLeft = 0;
var turnsMax = 0;


/**
 * @desc Remplace les caractères accentués d'une chaîne par leurs équivalents non-accentués
 * @param string Chaîne de caractères à traiter
 * @returns string Chaîne de caractères dépourvue d'accents
 */
function removeAccents(string) { 
	string = string.replace(new RegExp(/[àâä]/g),'a');
	string = string.replace(new RegExp(/[éèêë]/g),'e');
	string = string.replace(new RegExp(/[îï]/g),'i');
	string = string.replace(new RegExp(/[ôö]/g),'o');
	string = string.replace(new RegExp(/[ùûü]/g),'u');

	return string;
}


/**
 * @desc Génère un nouveau mot, ainsi que ses traductions
 */
function generateNewWords(){
	$.ajax({
		type: 'POST',
		url: 'php/ajax/jouer.php',
		data: 'play-again=1',
		dataType : 'xml',
		success: function(xml){
			enableAlphabeticalButtons();
			$('#hangman img').attr('src','pic/hangman/hangman-0.png');

			// Traitement du fichier XML
			turnsMax = turnsLeft = $(xml).find('turns').text();
			$(xml).find('writing').each(function(index){
				var word = $(this).text();
				generateWords[index] = word;

				// Création d'1 <li> par caractère qui compose le mot
				var contentHTML = '';
				for(var char=0;char<word.length;char++)
					contentHTML+= '<li>&nbsp;</li>';
				$('.word-to-find').eq(index).html(contentHTML);
			});
		},
		statusCode: {
			404: function() {
				alert('Impossible de générer un nouveau mot (erreur 404)');
				return false;
			}
		}
	});
}


/**
 * @desc Active ou désactive les boutons alphabétiques
 * @param enable Indique si les boutons doivent être actifs (choix par défaut) ou non
 */
function enableAlphabeticalButtons(enable){
	if(enable==null) enable = true;

	if(enable)	$('#play-form ul input').removeAttr('disabled');
	else		$('#play-form ul input').attr('disabled','disabled');
}


/**
 * @desc Active ou désactive un bouton alphabétique
 * @param char Caractère représenté par le bouton concerné
 * @param enable Indique si le bouton doit être actif (choix par défaut) ou non
 */
function enableAlphabeticalButton(char, enable){
	if(enable==null) enable = true;

	if(enable)	$('#play-form ul input[value='+char+']').removeAttr('disabled');
	else		$('#play-form ul input[value='+char+']').attr('disabled','disabled');
}


/**
 * @desc Vérifie que la lettre cliquée appartient au mot (ou à une ses traductions)
 * @param letter Lettre représenté par le bouton cliqué
 */
function checkChar(char){
	var charIsCorrect = false;
	enableAlphabeticalButton(char, false);

	for(var i=0;i<generateWords.length;i++){	// Pour chaque mot généré
		var word = generateWords[i];
		var wordWithoutAccents = removeAccents(word);

		var firstIndex = wordWithoutAccents.indexOf(char);
		var charsAnalysed = 0;
		// Tant que le caractère est présent dans la chaîne non-accentué
		while(firstIndex!=-1){
			charIsCorrect = true;
			charsAnalysed+= firstIndex + 1;
			$('.word-to-find').eq(i).find('li').eq(charsAnalysed-1).html(word.charAt(firstIndex));

			// Réduit la longueur de la chaîne
			word = word.substr(firstIndex+1);
			wordWithoutAccents = wordWithoutAccents.substr(firstIndex+1);

			// Cherche une nouvelle occurence du caractère dans la chaîne
			firstIndex = wordWithoutAccents.indexOf(char);
		}
	}

	if(!charIsCorrect){	// Le caractère est incorrect
		turnsLeft--;
		$('#hangman img').attr('src','pic/hangman/hangman-'+(turnsMax-turnsLeft)+'.png');
	}

	if(turnsLeft==0){
		enableAlphabeticalButtons(false);

		// Affichage des lettres manquantes
		$('.word-to-find').each(function(ulIndex){
			$(this).find('li').each(function(liIndex){
				if( jQuery.trim($(this).text()).length==0 ){
					$(this).addClass('lettre-manquante');
					$(this).text(generateWords[ulIndex].charAt(liIndex));
				}
			});
		});
	}
}


$(document).ready(function() {
	enableAlphabeticalButtons(false);
	generateNewWords();

	$("#play-again").click(
			function(){
				generateNewWords();
				return false;
			}
	);
	$("#play-form ul input").click(
			function(){
				checkChar($(this).attr('value'));
				return false;
			}
	);
});
