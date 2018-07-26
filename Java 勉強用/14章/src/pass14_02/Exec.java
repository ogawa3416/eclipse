package pass14_02;

public class Exec {
	public static void main(String[] args) {
		double []data={150.5, 75.1, 35.3, 281.2, 210.3};
		Range range = new Range(50.0, 250.0);
        for(double x : data){
            System.out.println(x + "\t: " + range.isOk(x));
        }

	}
}
