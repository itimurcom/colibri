// ================ CRC ================
// version: 1.39.01
// hash: 46c3db67ab9a1f6ba81acdb9d6f0017c5b1cc14b36b3b05cdbcb4ecb0231319e
// date: 17 September 2019 17:56
// ================ CRC ================
/* Norwegian initialisation for the jQuery UI date picker plugin. */
/* Written by Naimdjon Takhirov (naimdjon@gmail.com). */

( function( factory ) {
	if ( typeof define === "function" && define.amd ) {

		// AMD. Register as an anonymous module.
		define( [ "../widgets/datepicker" ], factory );
	} else {

		// Browser globals
		factory( jQuery.datepicker );
	}
}( function( datepicker ) {

datepicker.regional.no = {
	closeText: "Lukk",
	prevText: "&#xAB;Forrige",
	nextText: "Neste&#xBB;",
	currentText: "I dag",
	monthNames: [
		"januar",
		"februar",
		"mars",
		"april",
		"mai",
		"juni",
		"juli",
		"august",
		"september",
		"oktober",
		"november",
		"desember"
	],
	monthNamesShort: [ "jan","feb","mar","apr","mai","jun","jul","aug","sep","okt","nov","des" ],
	dayNamesShort: [ "søn","man","tir","ons","tor","fre","lør" ],
	dayNames: [ "søndag","mandag","tirsdag","onsdag","torsdag","fredag","lørdag" ],
	dayNamesMin: [ "sø","ma","ti","on","to","fr","lø" ],
	weekHeader: "Uke",
	dateFormat: "dd.mm.yy",
	firstDay: 1,
	isRTL: false,
	showMonthAfterYear: false,
	yearSuffix: ""
};
datepicker.setDefaults( datepicker.regional.no );

return datepicker.regional.no;

} ) );