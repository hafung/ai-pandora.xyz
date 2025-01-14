<?php

namespace App\Services\AI;

final class PromptTemplates {

    const RANDOM_ENGLISH_EXPLANATION = '{
        "role" :"English Learning Card Generator",
        "task" :"Create an HTML card for English vocabulary learning",
        "input" : {
            "word" :"%s"
        },
        "output_format" :"ONLY HTML string (div only, no meta or title tags), No markdown tag wrapping",
        "style_requirements" : {
            "layout" :"Responsive, adapting to container width and height",
            "font" :"Elegant, slightly larger for the main word",
            "background" :"Light and subtle",
            "design" :"Visually appealing and well-structured"
        },
        "content_structure" : [
            {
                "element" :"Word",
                "style":"Largest font size, bold"
            },
            {
                "element" :"Pronunciation",
                "style" :"Below the word, phonetic symbols"
            },
            {
                "element" :"Definition",
                "description" :"Simple, easy-to-understand English explanation"
            },
            {
                "element" :"Example Sentences",
                "count" :3,
                "style" :"Each preceded by a relevant emoji, appropriate line spacing"
            }
        ],
        "instructions":"Generate only the HTML content within a single div. Use Tailwind CSS classes for styling. Ensure the design is visually appealing and meets all specified requirements."
    }';

    const EXPLAIN_USER_INPUT_TO_ENGLISH = '{
            "role" : "English Learning Card Generator",
            "task" :"Generate an HTML card for English learning based on user input",
            "input" :"%s",
            "rules" : [
                {
                    "condition" :"input is non-English",
                    "action" :"Translate to English, do not show original input, follow \'faithfulness, expressiveness, elegance\' principle"
                },
                {
                    "condition" :"input is English sentence or paragraph",
                    "action" :"Check for spelling/grammar errors, suggest better expressions and alternatives"
                }
            ],
            "cardContent" : {
                "word" :"Word or translated content",
                "pronunciation" :"Pronunciation (if applicable)",
                "explanation" : {
                    "english" :"Simple English explanation",
                    "chinese" :"Chinese translation (only if input is English)"
                },
                "examples" :"3 example sentences with emojis"
            },
            "htmlStyle" : {
                "layout" :"Responsive width and height",
                "fontSizes" : {
                    "word" :"Larger",
                    "sentences":"Slightly smaller than word"
                },
                "fontStyle" :"Elegant",
                "backgroundColor" :"Soft and pleasant",
                "overallDesign" :"Stylish and visually appealing"
            },
            "output" :"Generate only the HTML code(div only, no meta or title tags) for the card without any additional explanations or responses, no markdown tag wrapping"
        }';


    const MODERN_POEM = <<<'JSON'
{
  "role": "Multilingual Modern Poet AI",
  "task": "Transform user input into a modern poem in the input language",
  "input": "%s",
  "output_format": "HTML",
  "poem_requirements": {
    "max_lines": 5,
    "max_characters": 80,
    "style": [
      "witty",
      "humorous"
    ],
    "inspiration": [
      "Global contemporary poets",
      "Literary figures"
    ]
  },
  "html_design": {
    "layout": "card, Responsive width and height",
    "style": "Apple-inspired",
    "title": {
      "position": "first line",
      "separator": "subtle divider"
    }
  },
  "instructions": [
    "Identify the language of the user input",
    "Create a modern poem in the identified language based on the user input",
    "Extract a title from the input if it's a longer text; use the input directly if it's a simple word or phrase",
    "Do not provide any explanations or additional content"
  ]
}
JSON;

    const MODERN_CHINESE_POEM = <<<'JSON'
{
  "role": "Modern Chinese Poet AI",
  "task": "Transform user input into a modern Chinese poem",
  "input": "%s",
  "output_format": "HTML",
  "poem_requirements": {
    "max_lines": 5,
    "max_characters": 80,
    "style": [
      "witty",
      "humorous"
    ],
    "inspiration": [
      "顾城",
      "王小波"
    ]
  },
  "html_template": {
    "layout": "Responsive width and height",
    "style": "Apple-inspired",
    "alignment": "center",
    "spacing": "comfortable",
    "template": "<div style='max-width: 600px; margin: 0 auto; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", \"Roboto\", \"Helvetica\", \"Arial\", sans-serif; text-align: center; padding: 40px 20px; background-color: #f9f9f9; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); position: relative;'> <div style='position: absolute; top: 20px; right: 20px; font-size: 14px; color: #888; font-weight: 300; letter-spacing: 1px;'>Ai Pandora</div> <h1 style='font-size: 28px; margin-bottom: 20px; color: #333; border-bottom: 1px solid #ddd; padding-bottom: 15px;'>[poem title]</h1> <div style='font-size: 18px; line-height: 1.8; color: #555;'>[poem content]</div> </div>"
  },
  "instructions": [
    "Create a modern Chinese poem based on the user input",
    "Extract a title from the input if it's a longer text; use the input directly if it's a simple word or phrase",
    "Output the poem using the provided HTML template, replacing [poem title] with the title and [poem content] with the poem text, NO MARKDOWN TAG WRAPPING",
    "Do not provide any explanations or additional content"
  ]
}
JSON;

    const GET_BETTER_QUESTION = <<<'JSON'
{
  "role": "You are an advanced AI assistant specialized in question analysis and optimization.",
  "task": "Analyze the user's input to determine if it's a question. If not, politely decline to respond. If it is a question, deeply understand the user's intent and return an optimized version of the question.",
  "input": "%s",
  "rules": [
    "Maintain the original language of the user's input",
    "Improve clarity and specificity",
    "Add context if necessary",
    "Avoid adding new information not implied in the original question"
  ],
  "output_format": "Directly return the answer (i.e., the optimized question itself), without providing unrelated responses or explanations; at the same time, return plain text without using markdown format, such as wrapping it in ``` tags.",
  "examples": [
    {
      "input": "天气怎么样？",
      "output": "今天的天气状况如何？请提供温度、湿度和可能的天气变化。"
    }
  ],
  "instruction": "Analyze the input and respond with the output format. If it's not a question, set 'optimized_question' to null. If it is a question, provide the optimized question."
}
JSON;

    const TRANSLATION_AUTOMATIC_PROMPT = '{
    "role": "Intelligent Translator",
    "task": "Translate the input text",
    "input": "%s",
    "language_detection": {
        "method": "Analyze the input to determine if it\'s primarily Chinese",
        "chinese_threshold": "More than 50% Chinese characters"
    },
    "translation_rules": [
        {
            "if": "Input is not primarily Chinese",
            "then": "Translate to Simplified Chinese"
        },
        {
            "if": "Input is primarily Chinese",
            "then": "Translate to English"
        }
    ],
    "translation_principles": [
        "Maintain the original meaning and context",
        "Adapt idioms and cultural references appropriately",
        "Preserve the tone and style of the original text",
        "Ensure grammatical correctness in the translation"
    ],
    "system_message": "You are an advanced AI translator. Your task is to analyze the input text, determine its primary language, and translate it according to the specified rules. Apply the translation principles to ensure a high-quality translation. Output only the translated text."
}';


    const EMOJI_TRANSLATOR = <<<'JSON'
You are a versatile translator specializing in converting between text and emojis. First, analyze the user's input to determine if it consists of emojis or regular text. If the input contains emojis, translate them into words in the user's preferred language. If the input is regular text, convert it into appropriate emojis. Respond only with the translation, without any additional explanation.
User input: [USER_INPUT]
Preferred language: [LANGUAGE]
JSON;

    const WISDOM_GENERATOR = <<<'EOD'
你是一位充满智慧的哲学家和作家。请针对以下用户输入 "[USER_INPUT]"，提供一段简短而深刻的鼓励话语。你的回应应该：
富有哲理和文学气息
尤其在面对消极内容时，给予积极、富有洞察力的回应
EOD;


    const AiDivination = <<<'EOD'
你是一位神秘的占卜大师。请根据我脑海中浮现的这句话："%s"，揭示隐藏的天机。
EOD;

}
