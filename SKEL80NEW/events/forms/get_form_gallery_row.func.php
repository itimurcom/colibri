<?
//..............................................................................
// возвращает код одного изображения поля формы
//..............................................................................	
function get_form_gallery_row($filename=NULL, $data=NULL)
	{
	if (!is_null($filename) AND file_exists(UPLOADS_ROOT.$filename))
		{
//		$image = get_thumbnail($filename, 'UPLOADED_AVA');
		
		return 
			TAB."<div class=\"uploaded\">".
			get_big_thumbnail(basename($filename), $class="avatar", $gallery_name="main", $title_gal="", 'UPLOADED_AVA').
			TAB."<div class='x'>".
			TAB."<span rel=\"{$filename}\" rel-data=\"{$data}\" onclick=\"uploaded_img_x(this);\">".
//				TAB."<img class=\"gal_remove\" src=\"/themes/".CMS_THEME."/images/delete_img_button.png\">".
				TAB."<img class=\"gal_remove\" src=\"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAA2RpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtcE1NOk9yaWdpbmFsRG9jdW1lbnRJRD0ieG1wLmRpZDpDRDY0QUQwN0Y5NURFMjExOEZFODg1NTJGRUI4RUY0MiIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDo3NkFGRkZCNjY4RUYxMUU0ODEyMjhBOTlDOTRDRjgwMCIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo3NkFGRkZCNTY4RUYxMUU0ODEyMjhBOTlDOTRDRjgwMCIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M1IFdpbmRvd3MiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpEMTk2MzdGNEVFNjhFNDExODE1MDk0OERDQ0FBQzQzRiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpDRDY0QUQwN0Y5NURFMjExOEZFODg1NTJGRUI4RUY0MiIvPiA8L3JkZjpEZXNjcmlwdGlv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\">".

			TAB."</span>".
			TAB."</div>".
			TAB."</div>".
			"";
		}
	}
?>
