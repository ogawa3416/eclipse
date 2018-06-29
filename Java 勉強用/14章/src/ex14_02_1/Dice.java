package ex14_02_1;
public class Dice {
	int		val;	// 目数
	String	color;	// サイコロの色
	public	Dice(int val, String color){
		this.val = val;
		this.color = color;
	}
	public	Dice(String color){
		this(1, color);
	}
	public	Dice(){
		this(1, "白");
	}	
	public void	play(){
		val = (int)(Math.random()*6) + 1; 
	}
}