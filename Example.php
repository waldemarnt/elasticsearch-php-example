<?php
include 'ElasticFactory.php';

$ef = new ElasticFactory('my_project');

$ef->setSize(100);
$ef->setType('users');

//elasticsearch auto generate a structure map, age is int
//and born is date, this types are auto created into elasticsearch data mapping
$data = [
	'name'=>'Waldemar Neto',
	'age'=>24,
	'email'=>'waldemarnt@outlook.com',
	'born'=>'1990/01/23'
];

$ef->setData($data);
$return = $ef->create();

//we can access the created index id, getting the return for this

echo $return['_id'].' has been saved <br/>';

//now , we can set a id for this object, and change a field value to update
$ef->setId(1);

$editData = [
	'name'=>'Waldemar Neto',
	'age'=>24,
	'email'=>'waldemarnt@outlook.com',
	'born'=>'1990/01/23'
];

$ef->setData($editData);
$updateReturn = $ef->update();

echo $updateReturn['_id'].' has been updated <br/>';

//find by age using match query type
$matchQuery=[
	'match'=> [
		'age'=>24
	]
];

$matchData = $ef->find($matchQuery);

//if have a result, elastic create two objects named as hits, with searched data.
if(isset($matchData['hits']['hits'])){
	foreach ($matchData['hits']['hits'] as $key => $hit) {
		echo $hit['_source']['name'].'<br/>';
	}
}

echo 'example with filters <br/>';

//example with filters
$filter = array();
$filter['term']['name'] = 'waldemar';

$query = array();
$query['match']['age'] = 24;

$filteredQuery = [
	'filtered'=>[
		'filter'=>$filter,
		'query'=>$query
	]
];


$filteredData = $ef->find($filteredQuery);

if(isset($filteredData['hits']['hits'])){
	foreach ($filteredData['hits']['hits'] as $key => $hit) {
		echo $hit['_source']['name'].'<br/>';
	}
}

echo 'example with date range filter <br/>';
//example with date range filter
$filter = array();
$filter['range']['born'] =[
	'gte'=>'1990/01/20',
	'lte'=>'1990/01/24'
];

$query = array();
$query['match_all'][] = [];

$filteredByBorn = [
	'filtered'=>[
		'filter'=>$filter,
		'query'=>$query
	]
];


$filteredBorn = $ef->find($filteredByBorn);

if(isset($filteredBorn['hits']['hits'])){
	foreach ($filteredBorn['hits']['hits'] as $key => $hit) {
		echo $hit['_source']['name'].'<br/>';
	}
}