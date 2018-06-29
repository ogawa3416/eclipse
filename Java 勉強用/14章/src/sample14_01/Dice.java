package sample14_01;
public class Dice {
	int		val;	
	// コンストラクタ
	public	Dice(int num){	// 引数numは初期値としてセットしたい値
		val = num;			// 引数をvalに代入して初期化する
	}
	public void	play(){
		val = (int)(Math.random()*6) + 1; 
	}
}