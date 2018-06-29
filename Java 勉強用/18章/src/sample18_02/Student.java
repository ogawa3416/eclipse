package sample18_02;

public class Student extends Member {
	private	String	studentId;
	public	Student(int id, String name, String studentId){
		super(id ,name);
		this.studentId	=	studentId;
	}
	public	double	discount(){
		return	0.2;	// 学生割引の率を返す（2割引き）
	}
	public String getStudentId() {
		return studentId;
	}
}

