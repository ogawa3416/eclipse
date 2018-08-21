package com.example.demo;

import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.RequestMapping;

@Controller
public class DemoController {
	@RequestMapping("/")
	public String index() {
		return "index";
	}

	@RequestMapping("/confirm")
	public String confirm(@ModelAttribute("msg1") String arg1, @ModelAttribute("msg2") String arg2) {
		System.out.println("msg1:" + arg1);
		System.out.println("msg2:" + arg2);
		return "index";
	}
}