package sample;

public class Dice2 {
	private	int		val;	// 目数
	private	String	color;	// サイコロの色
	public	Dice2(int val, String color){
		this.val = val;
		this.color = color;
	}
	public	Dice2(String color){
		this(1, color);
	}
	public	Dice2(){
		this(1, "白");
	}	
	void	play(){				// 他のパッケージからは使えない
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

