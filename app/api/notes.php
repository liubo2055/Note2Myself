<?php

/*
    Notes API 
    
    API Methods
    
    /notes  {get}           - Get all Notes
    /notes/:id {post}       - Update/Insert this note
    /notes/:id {get}        - Get this note
    /notes/:id {delete}     - Delete this note
    /notes/force {delete}   - Delete all notes (special)
    

*/


// For test we are going to just echo out some sample JSON data.

$sampleNotes="[{
		'title': 'Sample Title A'
	,	'description': 'Sample description for this note.'
	,	'userid': '0001'
	,	'text':'This is a sample note text.'
	,	'type':'text'
},
{
		'title': 'Sample Title B'
	,	'description': 'Sample description for this note.'
	,	'userid': '0001'
	,	'text':'This is a sample note text.'
	,	'type':'text'
},
{
		'title': 'Sample Title C'
	,	'description': 'Sample description for this note.'
	,	'userid': '0001'
	,	'text':'This is a sample note text.'
	,	'type':'text'
},
{
		'title': 'Sample Title D'
	,	'description': 'Sample description for this note.'
	,	'userid': '0001'
	,	'text':'This is a sample note text.'
	,	'type':'text'
},
{
		'title': 'Sample Title E'
	,	'description': 'Sample description for this note.'
	,	'userid': '0001'
	,	'text':'This is a sample note text.'
	,	'type':'text'
},
{
		'title': 'Sample Title F'
	,	'description': 'Sample description for this note.'
	,	'userid': '0001'
	,	'text':'This is a sample note text.'
	,	'type':'text'
},
{
		'title': 'Sample Title G'
	,	'description': 'Sample description for this note.'
	,	'userid': '0001'
	,	'text':'This is a sample note text.'
	,	'type':'text'
},
{
		'title': 'Sample Title H'
	,	'description': 'Sample description for this note.'
	,	'userid': '0001'
	,	'text':'This is a sample note text.'
	,	'type':'text'
},
{
		'title': 'Sample Title I'
	,	'description': 'Sample description for this note.'
	,	'userid': '0001'
	,	'text':'This is a sample note text.'
	,	'type':'text'
}]";
echo $sampleNotes;
exit;