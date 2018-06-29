package sample13_03;

public class Dice {
	int		val;
	public void	play(){
		val = (int)(Math.random()*6) + 1; // 1～6のどれかをnに代入する
	}
}
