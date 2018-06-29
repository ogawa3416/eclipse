package sample;

public class Dice {
	private	int		val;	// 目数
	private	String	color;	// サイコロの色
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
	public int getVal() {
		return val;
	}
	public String getColor() {
		return color;
	}
	public String toString(){
		return	val + "/" + color;
	}
}

