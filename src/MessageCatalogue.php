<?php
/**
 * Part of the Joomla Framework Language Package
 *
 * @copyright  Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Language;

/**
 * Catalogue of loaded translation strings for a language
 *
 * @since  __DEPLOY_VERSION__
 */
class MessageCatalogue
{
	/**
	 * A fallback for this catalogue
	 *
	 * @var    MessageCatalogue
	 * @since  __DEPLOY_VERSION__
	 */
	private $fallbackCatalogue;

	/**
	 * The language of the messages in this catalogue
	 *
	 * @var    string
	 * @since  __DEPLOY_VERSION__
	 */
	private $language;

	/**
	 * The messages stored to this catalogue
	 *
	 * @var    array
	 * @since  __DEPLOY_VERSION__
	 */
	private $messages = [];

	/**
	 * MessageCatalogue constructor.
	 *
	 * @param   string  $language  The language of the messages in this catalogue
	 * @param   array   $messages  The messages to seed this catalogue with
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function __construct(string $language, array $messages = [])
	{
		$this->language = $language;

		$this->addMessages($messages);
	}

	/**
	 * Add a message to the catalogue, replacing the key if it already exists
	 *
	 * @param   string  $key      The key identifying the message
	 * @param   string  $message  The message for this key
	 *
	 * @return  void
	 */
	public function addMessage(string $key, string $message)
	{
		$this->addMessages([$key => $message]);
	}

	/**
	 * Add messages to the catalogue, replacing any keys which already exist
	 *
	 * @param   array  $messages  An associative array containing the messages to add to the catalogue
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function addMessages(array $messages)
	{
		$this->messages = array_replace($this->messages, array_change_key_case($messages, CASE_UPPER));
	}

	/**
	 * Check if this catalogue has a message for the given key, ignoring a fallback if defined
	 *
	 * @param   string  $key  The key to check
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function definesMessage(string $key): bool
	{
		return isset($this->messages[strtoupper($key)]);
	}

	/**
	 * Get the fallback for this catalogue if set
	 *
	 * @return  MessageCatalogue|null
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getFallbackCatalogue(): string
	{
		return $this->fallbackCatalogue;
	}

	/**
	 * Get the language for this catalogue
	 *
	 * @return  string
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getLanguage(): string
	{
		return $this->language;
	}

	/**
	 * Get the message for a given key
	 *
	 * @param   string  $key  The key to get the message for
	 *
	 * @return  string  The message if one is set otherwise the key
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getMessage(string $key): string
	{
		if (isset($this->messages[strtoupper($key)]))
		{
			return $this->messages[strtoupper($key)];
		}

		if ($this->fallbackCatalogue)
		{
			return $this->fallbackCatalogue->getMessage($key);
		}

		return strtoupper($key);
	}

	/**
	 * Fetch the messages stored in this catalogue
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getMessages(): array
	{
		return $this->messages;
	}

	/**
	 * Check if the catalogue has a message for the given key
	 *
	 * @param   string  $key  The key to check
	 *
	 * @return  boolean
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function hasMessage(string $key): bool
	{
		if (isset($this->messages[strtoupper($key)]))
		{
			return true;
		}

		if ($this->fallbackCatalogue)
		{
			return $this->fallbackCatalogue->hasMessage($key);
		}

		return false;
	}

	/**
	 * Merge another catalogue into this one
	 *
	 * @param   MessageCatalogue  $messageCatalogue  The catalogue to merge
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 * @throws  \LogicException
	 */
	public function mergeCatalogue(MessageCatalogue $messageCatalogue)
	{
		if ($messageCatalogue->getLanguage() !== $this->getLanguage())
		{
			throw new \LogicException('Cannot merge a catalogue that does not have the same language code.');
		}

		$this->addMessages($messageCatalogue->getMessages());
	}

	/**
	 * Set the fallback for this catalogue
	 *
	 * @param   MessageCatalogue  $messageCatalogue  The catalogue to use as the fallback
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function setFallbackCatalogue(MessageCatalogue $messageCatalogue)
	{
		$this->fallbackCatalogue = $messageCatalogue;
	}
}
