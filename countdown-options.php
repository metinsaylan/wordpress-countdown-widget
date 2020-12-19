<?php

function wcw_help_icon($hash){
	return " <a href=\"https://wpassist.me/plugins/countdown/help/#".$hash."\" target=\"_blank\" class=\"help-icon\">(?)</a>";
}

// Define plugin options
$options = array(
	array(
		"name" => "labels",
		"label" => __("Edit Labels"),
		"type" => "section"
	),

	array( "type" => "open" ),

		array(  "name" => "Years",
			"desc" => "Text to be displayed instead of 'Years'.".wcw_help_icon("translation"),
			"id" => "label_years",
			"std" => __("Years"),
			"type" => "text"),

	  array(  "name" => "Year (singular)",
				"desc" => "Text to be displayed instead of 'Year'.".wcw_help_icon("translation"),
				"id" => "label_year",
				"std" => __("Year"),
				"type" => "text"),

		array(  "name" => "Months",
			"desc" => "Text to be displayed instead of 'Months'.".wcw_help_icon("translation"),
			"id" => "label_months",
			"std" => __("Months"),
			"type" => "text"),

			array(  "name" => "Month (singular)",
				"desc" => "Text to be displayed instead of 'Month'.".wcw_help_icon("translation"),
				"id" => "label_month",
				"std" => __("Month"),
				"type" => "text"),

		array(  "name" => "Weeks",
			"desc" => "Text to be displayed instead of 'Weeks'.".wcw_help_icon("translation"),
			"id" => "label_weeks",
			"std" => __("Weeks"),
			"type" => "text"),

			array(  "name" => "Week (singular)",
				"desc" => "Text to be displayed instead of 'Week'.".wcw_help_icon("translation"),
				"id" => "label_week",
				"std" => __("Week"),
				"type" => "text"),

		array(  "name" => "Days",
			"desc" => "Text to be displayed instead of 'Days'.".wcw_help_icon("translation"),
			"id" => "label_days",
			"std" => __("Days"),
			"type" => "text"),

			array(  "name" => "Day (singular)",
				"desc" => "Text to be displayed instead of 'Day'.".wcw_help_icon("translation"),
				"id" => "label_day",
				"std" => __("Day"),
				"type" => "text"),

		array(  "name" => "Hours",
			"desc" => "Text to be displayed instead of 'Hours'.".wcw_help_icon("translation"),
			"id" => "label_hours",
			"std" => __("Hours"),
			"type" => "text"),

			array(  "name" => "Hour",
				"desc" => "Text to be displayed instead of 'Hour'.".wcw_help_icon("translation"),
				"id" => "label_hour",
				"std" => __("Hour"),
				"type" => "text"),

		array(  "name" => "Minutes",
			"desc" => "Text to be displayed instead of 'Minutes'.".wcw_help_icon("translation"),
			"id" => "label_minutes",
			"std" => __("Minutes"),
			"type" => "text"),

			array(  "name" => "Minute",
				"desc" => "Text to be displayed instead of 'Minute'.".wcw_help_icon("translation"),
				"id" => "label_minute",
				"std" => __("Minute"),
				"type" => "text"),

		array(  "name" => "Seconds",
			"desc" => "Text to be displayed instead of 'Seconds'.".wcw_help_icon("translation"),
			"id" => "label_seconds",
			"std" => __("Seconds"),
			"type" => "text"),

			array(  "name" => "Second",
				"desc" => "Text to be displayed instead of 'Second'.".wcw_help_icon("translation"),
				"id" => "label_second",
				"std" => __("Second"),
				"type" => "text"),

	array( "type" => "close" )

);
