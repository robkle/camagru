<?php

enum ConfirmStatus
{
	case Success;
	case QueryInvalid;
	case SystemFailure;
	case AccountInvalid;
	case AccountConfirmed;
}
