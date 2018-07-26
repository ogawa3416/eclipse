package pass14_02;

public class Range {
	private double	min;						// 最小値
	private double	max;						// 最大値
	
	
	public Range(double min, double max) {
		this.min = min;
		this.max = max;
	}
	public boolean isOk(double a) {
		return a<max && a>=min ? true : false;
	}
	public String toString() {
		return "[min:" + min + " - max:" + max + "]";
	}
	
	
	public double getMin() {
		return min;
	}
	public void setMin() {
		this.min = min;
	}
	public double getMax() {
		return max;
	}
	public void setMax() {
		this.max = max;
	}
}

