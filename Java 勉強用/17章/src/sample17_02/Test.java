package sample17_02;
class Data {
	public Data() {
		System.out.println("Data クラス");
	}
}
class BigData extends Data {
	public BigData() {
		System.out.println("BigData クラス");
	}
}
class ManyBigData extends BigData {
	public ManyBigData() {
		System.out.println("ManyBigData クラス");
	}
}
public class Test {
	public static void main(String[] args) {
		ManyBigData dt = new ManyBigData();
	}
}