<?php

enum ModifyEmailStatus
{
	case Success;
	case Unauthorised;
	case InvalidEmail;
	case SystemFailure;
}
