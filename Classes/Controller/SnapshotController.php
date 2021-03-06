<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Nils Blattner <nb@cabag.ch>, cab services ag
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 *
 *
 * @package contentstage
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Tx_Contentstage_Controller_SnapshotController extends Tx_Contentstage_Controller_BaseController {

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		$snapshots = $this->snapshotRepository->findAll();
		$this->view->assign('snapshots', $snapshots);
	}

	/**
	 * action create
	 *
	 * @param string $type 'local' or 'remote'.
	 * @return void
	 */
	public function createAction($type = Tx_Contentstage_Domain_Repository_ContentRepository::TYPE_LOCAL) {
		$info = $this->getLocalDbInfo();
		$repository = $this->localRepository;
		
		if ($type === Tx_Contentstage_Domain_Repository_ContentRepository::TYPE_REMOTE) {
			$info = $this->extensionConfiguration['remote.']['db.'];
			$repository = $this->remoteRepository;
		}
		
		try {
			$tables = $this->filterTables(array_keys($repository->getTables()), $this->ignoreSnapshotTables);
			$info = $this->snapshotRepository->create($tables, $info, $type);
			$this->log->log($this->translate('info.snapshot.done', array(basename($info['file']))), Tx_CabagExtbase_Utility_Logging::OK);
		} catch (Exception $e) {
			$this->log->log($this->translate('error.' . $e->getCode(), array($e->getMessage())) ?: $e->getMessage(), Tx_CabagExtbase_Utility_Logging::ERROR);
		}
		
		$this->log->write();
		$this->redirect('list');
	}

	/**
	 * Action delete
	 *
	 * @param string $snapshot Delete this file.
	 * @return void
	 */
	public function deleteAction($snapshot) {
		$this->snapshotRepository->remove($snapshot);
		$this->log->log($this->translate('info.snapshot.removed', array($snapshot)), Tx_CabagExtbase_Utility_Logging::OK);
		$this->log->write();
		$this->redirect('list');
	}

	/**
	 * Action revert
	 *
	 * @param string $snapshot Revert to this file.
	 * @param string $type 'local' or 'remote'.
	 * @return void
	 */
	public function revertAction($snapshot, $type = Tx_Contentstage_Domain_Repository_ContentRepository::TYPE_LOCAL) {
		$info = $this->getLocalDbInfo();
		$repository = $this->localRepository;
		
		if ($type === Tx_Contentstage_Domain_Repository_ContentRepository::TYPE_REMOTE) {
			$info = $this->extensionConfiguration['remote.']['db.'];
			$repository = $this->remoteRepository;
		}
		
		try {
			$tables = $this->filterTables(array_keys($repository->getTables()), $this->ignoreSnapshotTables);
			$newInfo = $this->snapshotRepository->create($tables, $info, $type);
			$this->log->log($this->translate('info.snapshot.done', array(basename($newInfo['file']))), Tx_CabagExtbase_Utility_Logging::OK);
			$revertInfo = $this->snapshotRepository->revert($snapshot, $info);
			$this->log->log($this->translate('info.snapshot.reverted', array(basename($revertInfo['file']), $type)), Tx_CabagExtbase_Utility_Logging::OK);
		} catch (Exception $e) {
			$this->log->log($this->translate('error.' . $e->getCode(), array($e->getMessage())) ?: $e->getMessage(), Tx_CabagExtbase_Utility_Logging::ERROR);
		}
		
		$this->log->write();
		$this->redirect('list');
	}

}
?>