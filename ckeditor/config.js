/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.extraPlugins = 'confighelper';
	config.removeDialogFields='image:info:txtBorder';
	config.smiley_path=CKEDITOR.basePath+'plugins/smiley/msn/';
	config.smiley_images=['ah1n1.gif','angel.gif','angry.gif','applause.gif','bazar.gif','beat.gif','beer.gif','beer2.gif','blind.gif','bokali.gif','boyan.gif','bravo.gif','burumburum.gif','bye.gif','call.gif','car.gif','chih.gif','cool.gif','crazy.gif','cry.gif','cup_full.gif','cvetok.gif','dada.gif','dance.gif','death.gif','devil.gif','draznilka.gif','drink.gif','drunk.gif','druzhba.gif','eda.gif','elka.gif','fingal.gif','foo.gif','football.gif','fuck.gif','girlkiss.gif','hammer.gif','heart.gif','help.gif','hug.gif','huh.gif','hypnosis.gif','kill.gif','kiss.gif','letsrock.gif','lol.gif','look.gif','love.gif','mmmm.gif','money.gif','moroz.gif','nevizhu.gif','nini.gif','omg.gif','parik.gif','phone.gif','podarok.gif','podmig.gif','podzatylnik.gif','poka.gif','pomada.gif','popa.gif','prey.gif','privet.gif','prostite.gif','question.gif','rofl.gif','rose.gif','sad.gif','shedevr.gif','shock.gif','sila.gif','skuchno.gif','sleepy.gif','smeh.gif','smile.gif','smirk.gif','smoke.gif','smutili.gif','snegurka.gif','spasibo.gif','stena.gif','stop.gif','suicide.gif','tits.gif','tort.gif','tost.gif','uhmylka.gif','umnik.gif','unsmile.gif','ura.gif','vkaske.gif','wakeup.gif','whosthat.gif','yazyk.gif','zlo.gif','zombobox.gif'];
	config.smiley_descriptions=['Virus', 'Ange', 'Colère', 'Applause', 'Bazar', 'Beat','Bière','Bière à 2','Blind','A la votre !','Boyan','Bravo','burumburum','adieu','appelle moi','caisse','atchoum','cool','gogole','triste','tasse pleine','cvetok','oui oui','dance','mort','endiablé','blblablbla','bon breuvage !','cul sec !','check','se faire cuir un oeuf','sapin noël','un air de...','nul !','football','fuck','girlkiss','marteau','coeur','aidez-moi','hug','dégeu','hypnosis','I kill you','bisou','rock','LOL','look','love','mmmm','money...','moroz','ni vu','neni','OMG','parik','phone','cadeau','clin d\'oeil','maboule','salutavoussi','chic','popa','prey','happy','desapointé','question ?','MDR','rose','sad','la joconde','What ?','choco','boring','zzzzz','haha',':)',';)','smoke','shy','snegurka','de rien','aaarg','halt','smell','tits','cerise sur le gâteau','un verre ?','|D','illumin..','aï','Youpi !','securité','c trop calme','Incognito','lalalère','hanfry','TV'];
	config.filebrowserImageBrowseUrl = 'elfinder/elfinder.html?type=image';
	config.filebrowserImageUploadUrl = 'accessoires/upload.php?type=image';
	config.filebrowserWindowHeight = 518
};

CKEDITOR.on( 'dialogDefinition', function( ev )
{
	// Take the dialog name and its definition from the event data.
	var dialogName = ev.data.name;
	var dialogDefinition = ev.data.definition;

	// Check if the definition is from the dialog window you are interested in (the "Link" dialog window).
	if ( dialogName == 'image' )
	{
		var advancedTab = dialogDefinition.getContents( 'advanced' );
		var classField = advancedTab.get( 'txtGenClass' );
		classField['default'] = 'img-responsive'; // Add class

		/*dialogDefinition.onShow = function () {
		 this.getContentElement("advanced","txtGenClass").disable(); // info is the name of the tab and width is the id of the element inside the tab
		 }*/
	}
});