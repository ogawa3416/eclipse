package exercise;

public class Pass07_02 {

	public static void main(String[] args) {
		
		String name[] = {"田中", "中村", "鈴木", "山本", "本田"};
		double kokugo[] = {82, 85, 74, 90, 70};
		double eigo[] = {70, 74, 88, 74, 82};
		
		double kokugokei = 0;
		double eigokei = 0;
		
		for(double a : kokugo) {
			kokugokei += a;
		}
		System.out.println("国語平均=" + (kokugokei / kokugo.length));
		
		for(double a : eigo) {
			eigokei += a;
		}
		System.out.println("英語平均=" + (eigokei / eigo.length));
		
		System.out.println("科目平均=" + (kokugokei + eigokei) / (kokugo.length + eigo.length));
		
		for(int i=0; i<name.length; i++) {
			System.out.println(name[i] + " : " + (kokugo[i] + eigo[i]) / 2);
		}
		
		

	}

}
