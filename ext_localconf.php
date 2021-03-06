<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$TYPO3_CONF_VARS['FE']['eID_include']['tx_contentstage'] = 'EXT:contentstage/Classes/Eid/ClearCache.php';

$pageTS = <<<'EOD'
tx_contentstage {
	doNotSync =
	doNotSnapshot =
	doNotDisplay {
		__all {
			l18n_diffsource = 1
			l10n_diffsource = 1
			SYS_LASTCHANGED = 1
		}
	}
	
	// if this is set to 0, only files and folders are pushed
	pushDependencies = 1
	
	depthOptions = 0,1,2,3,4,5,6,7,8,9,-1
	defaultDepth = -1
	minimumDepth = 0
	maximumDepth = -1
	
	useHttpsLocal = 0
	useHttpsRemote = 0
	
	overrideDomainLocal =
	overrideDomainRemote =
	
	review = 0
	review {
		// the amount of backend users that are needed to review
		required = 2
		// if this is set, review records may be created
		editCreate = 0
		// if this is set, push may be used even when the review is not "reviewed"
		mayPush = 0
		// explicit list of backend groups that are supposed to review
		groups = 0
		// default auto push to yes for new reviews
		defaultAutoPush = 0
		// automatically set to reviewed if a person sets him/herself as reviewer
		autoReviewIfSelf = 1
		// send mail to active user if found
		sendMailToCurrentUser = 0
	}
	
	mails {
		default {
			templateFile = 
			from = info@example.com
			fromName = example.com - Contentstage (noreply)
			html = 0
			sendToReviewers = 1
			to {
				/*
				# allows to send additional mails to defined email addresses
				0 {
					name = Example info mail
					mail = info@example.com
				}
				*/
			}
		}
		
		reviewChanged {
			templateFile = EXT:contentstage/Resources/Private/Backend/Mails/ReviewChanged.html
		}
		
		reviewCreated {
			templateFile = EXT:contentstage/Resources/Private/Backend/Mails/ReviewCreated.html
		}
		
		reviewPushed {
			templateFile = EXT:contentstage/Resources/Private/Backend/Mails/ReviewPushed.html
		}
	}
}
EOD;

t3lib_extMgm::addPageTSConfig($pageTS);
?>
