<?


echo json_encode(array('templates'=>array(	array('id'=>'1','name'=>'Car Ready','text'=>'Dear {name}, your car will be ready at {time}','times_used'=>1,'fields_required'=>array('name','time')),
											array('id'=>'2','name'=>'Template 2','text'=>'dear someone or other','times_used'=>2,'fields_required'=>array('name','time','time2'))
										)));