<?php

enum PswdRenewStatus
{
	case Success;
	case Unauthorised;
	case InvalidPassword;
	case ConflictPassword;
	case SystemFailure;
}
