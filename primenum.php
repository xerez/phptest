<?PHP

/***
☆☆☆☆☆	
☆☆☆☆☆	落ち着け、素数を数えるんだ。
☆☆☆☆☆	PHP練習 ※gmp_nextprimeを使っちゃダメ
☆☆☆☆☆	
****/

//素数の倍数は素数ではない
//素数でない数は必ずその数の平方根以下に分解できる

function primenum($maxnum){
	//平方根を返す sqrt　切り上げ　ceil()
	$sqroot = ceil(sqrt($maxnum));
	//奇数のみ
	$oddlist = array_flip(range(3, $maxnum, 2));
	
	for($i=2; $i<=$sqroot; $i++){
		if(isset($oddlist[$i])){
			for($j=$i*2; $j<=$maxnum; $j+=$i){
				unset($oddlist[$j]);
			}
		}
	}

	$list = array_keys($oddlist);
	array_unshift($list,2);

	return $list;
}

//var_dump(primenum(10000));
