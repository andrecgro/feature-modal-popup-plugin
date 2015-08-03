var i=0;

function mudaEstilo(id){

if(i%2 == 0){
  document.getElementById(id).style.display = 'block';
  i++;
}
else{
  document.getElementById(id).style.display = 'none';
  i++;
}

}
