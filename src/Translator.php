<?php
/**
 * User: Aleksandar Zivanovic
 */

namespace Translate;

class Translator
{
    const DEFAULT_SECTION_NAME = 'default';

    /** @var string */
    private $translationsDirectory;

    /** @var string */
    private $language;

    /** @var bool */
    private $isParsed = false;

    /** @var array */
    private $sections;

    /**
     * @param string $language
     * @param string $translationsDirectory
     * @throws TranslatorException
     */
    public function __construct(string $language, string $translationsDirectory = '')
    {
        $this->language = trim($language, ' /\\');
        $this->translationsDirectory = '/' . trim($translationsDirectory, ' /\\');

        $path = $this->getTranslationPath();

        if (false === is_readable($path)) {
            throw new TranslatorException("Invalid language configuration $path}");
        }

        $this->sections = [];
    }

    /**
     * @param string $id
     * @param array $data
     * @param string $section
     * @param bool $strict
     * @return null|string
     * @throws TranslatorException
     */
    public function translate(
        string $id, array $data = [], string $section = self::DEFAULT_SECTION_NAME, bool $strict = false
    ): ?string
    {
        if (false === $this->isParsed) {
            $this->parse();
        }

        $translation = ($this->sections[$section][$id] ?? '');

        if ($translation) {
            return str_replace(array_keys($data), $data, $translation);
        }

        if ($strict) {
            throw new TranslatorException("Translation for {$id} in {$section} section, does not exist.");
        }

        if (self::DEFAULT_SECTION_NAME !== $section) {
            return null;
        }

        foreach ($this->sections as $otherSection => $translations) {
            $translation = $this->translate($id, $data, $otherSection, false);

            if ($translation) {
                return $translation;
            }
        }

        return null;
    }

    /**
     * Retrieve full path to translation file
     *
     * @return string
     */
    public function getTranslationPath(): string
    {
        return "{$this->translationsDirectory}/{$this->language}.ini";
    }

    /**
     * @return void
     * @throws TranslatorException
     */
    private function parse(): void
    {
        $handle = fopen($this->getTranslationPath(), 'r');

        if (false === $handle) {
            throw new TranslatorException("Failed to parse translation configuration");
        }

        $section = self::DEFAULT_SECTION_NAME;

        while (false !== ($line = fgets($handle))) {
            $translationLine = $this->getTranslationLine($line);

            if (null === $translationLine) {
                continue;
            } else if (TranslationLine::LINE_TYPE_SECTION === $translationLine->getType()) {
                $section = $translationLine->getIdentifier();

                $this->sections[$section] = $this->sections[$section] ?? [];
            } else if (TranslationLine::LINE_TYPE_TRANSLATION === $translationLine->getType()) {
                $this->sections[$section][$translationLine->getIdentifier()] = $translationLine;
            }
        }

        fclose($handle);

        $this->isParsed = true;
    }

    /**
     * Parse single translation file line
     *
     * @param string $line
     * @return null|TranslationLine
     */
    private function getTranslationLine(string $line): ?TranslationLine
    {
        $line = trim($line);

        if (0 === strlen($line)) {
            return null;
        }

        if ('[' === $line[0] && ']' === substr($line, -1)) {
            return new TranslationLine(substr($line, 1, -1), '', TranslationLine::LINE_TYPE_SECTION);
        }

        list($identifier, $translation) = explode('=', $line, 2);

        if ($identifier && $translation) {
            return new TranslationLine($identifier, $translation, TranslationLine::LINE_TYPE_TRANSLATION);
        }

        return null;
    }
}