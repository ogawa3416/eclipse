package pass14_01;
public class HealthRecord {
	private String	name;					// 氏名
	private double	height;					// 身長 （m単位）
	
	
	public HealthRecord(String name, double height) {
		this.name = name;
		this.height = height;
	}
	public HealthRecord(double height) {
		this("", height);
	}
	
	public	double 	stdWeight(){	// 身長から計算した標準体重を返す
		return	Math.pow(height, 2) * 22;
	}	
	public 	String	toString(){		// フィールド変数の文字列表現を返す
		return	name + "/" + height + "m";
	}
	
	public String getName() {
		return name;
	}
	public void setName() {
		this.name =name;
	}
	public double getHeight() {
		return height;
	}
	public void setHeight() {
		this.height = height;
	}
}


