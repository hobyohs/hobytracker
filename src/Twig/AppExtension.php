<?php

// src/Twig/AppExtension.php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
	public function getFilters(): array
	{
		return [
			new TwigFilter('parse_likert', [$this, 'parseLikert']),
			new TwigFilter('parse_boolean', [$this, 'parseBoolean']),
			new TwigFilter('parse_hire_rating', [$this, 'parseHireRating']),
			new TwigFilter('parse_recommendation', [$this, 'parseRecommendation']),
		];
	}

	public function parseLikert(string $input): string
	{
		 switch($input) {
			case "1":
				$str = "Strongly Disagree";
				break;
			case "2":
				$str = "Disagree";
				break;
			case "3":
				$str = "Neutral";
				break;
			case "4":
				$str = "Agree";
				break;
			case "5":
				$str = "Strongly Agree";
				break;
			default:
			   $str = "undefined";
		}
		
		return $str;
	}
	
	public function parseBoolean(string $input): string
	{
		 switch($input) {
			case "0":
				$str = "No";
				break;
			case "1":
				$str = "Yes";
				break;
			default:
			   $str = "undefined";
		}
		
		return $str;
	}
	
	public function parseHireRating(string $input): string
	{
		 switch($input) {
			case "1":
				$str = "Strong No Hire";
				break;
			case "2":
				$str = "No Hire";
				break;
			case "3":
				$str = "Hire";
				break;
			case "4":
				$str = "Strong Hire";
				break;
			default:
			   $str = "Undefined";
		}
		
		return $str;
	}
	
	public function parseRecommendation(string $input): string
	{
		 switch($input) {
			case "0":
				$str = "Not Recommended";
				break;
			case "1":
				$str = "Recommended";
				break;
			default:
			   $str = "undefined";
		}
		
		return $str;
	}
}

?>