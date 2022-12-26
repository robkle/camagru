<?php

enum UploadStatus
{
	case Success;
	case InvalidUser;
	case UploadError;
	case NoSource;
	case InvalidType;
	case SizeLimit;
	case SystemFailure;
}
