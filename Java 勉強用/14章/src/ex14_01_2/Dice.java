package ex14_01_2;
public class Dice {
	int		val;
	// コンストラクタ
	public	Dice(){
		System.out.println("ランダムに初期化");
		play();
	}
	public void	play(){
		val = (int)(Math.random()*6) + 1; 
	}
}