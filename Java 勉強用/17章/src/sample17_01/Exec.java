package sample17_01;
public class Exec {
	public static void main(String[] args) {
		Student	stuMember = new Student(118, "田中宏", "A711");
		System.out.println( stuMember.getId()+"/"+
				 			stuMember.getName()+"/"+
				 			stuMember.getStudentId());

	}
}
