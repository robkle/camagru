<?php

enum Status
{
	case Success;
	case AccountInvalid;
	case AccountConfirmed;
	case AccountUnconfirmed;
	case CommentTooLong;
	case ConflictPassword;
	case ExistingEmail;
	case ExistingLogin;
	case InvalidEmail;
	case InvalidLogin;
	case InvalidOption;
	case InvalidPassword;
	case InvalidType;
	case InvalidUser;
	case InvalidUsername;
	case NoDestination;
	case NonExistent;
	case NoSource;
	case QueryInvalid;
	case SizeLimit;
	case SystemFailure;
	case TimeOut;
	case Unauthorised;
	case UploadError;
}
