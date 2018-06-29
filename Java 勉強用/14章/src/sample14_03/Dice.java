package sample14_03;
public class Dice {
	int		val;	
	// コンストラクタ
	public	Dice(int val){
		this.val = val;		// 引数の値を目数にセットする
	}
	public Dice(){
		val = 1; 			// 目数は1にする
	}
	public void	play(){
		val = (int)(Math.random()*6) + 1; 
	}
}