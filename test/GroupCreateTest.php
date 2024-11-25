<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "start.php";

use
	PHPUnit\Framework\TestCase,
	Elector\Group;

final class GroupCreateTest extends TestCase {
	public function testCreate(): void {
		$uid = Group::create("test");
		$group = new Group($uid);

		$this->assertIsArray($group->getInformations());
	}
}
