<?php

enum ChangeNotificationsStatus
{
	case Success;
	case Unauthorised;
	case InvalidOption;
	case SystemFailure;
}
