package pass13_01;

public class Exec {

	public static void main(String[] args) {
		Denpyo denpyo1 = new Denpyo();
		Denpyo denpyo2 = new Denpyo();
		
		denpyo1.date = "01-15";
		denpyo1.item = "パソコン";
		denpyo1.price = 50000;
		denpyo1.number = 1;
		
		denpyo2.date = "01-16";
		denpyo2.item = "コピー用紙";
		denpyo2.price = 600;
		denpyo2.number = 5;
		
		denpyo1.disp();
		denpyo2.disp();


	}

}
