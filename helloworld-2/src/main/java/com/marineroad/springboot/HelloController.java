package com.marineroad.springboot;

import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

@RestController // 
public class HelloController {

	@RequestMapping("/he") 
	public String hello() {
		return "Hello World !!";
	}

}