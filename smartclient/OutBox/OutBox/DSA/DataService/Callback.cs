using Microsoft.Practices.SmartClient.DisconnectedAgent;
using System;
using Sante.EMR.SmartClient.OutBox.DataWebService;
using Sante.EMR.SmartClient.Infrastructure.Interface;

namespace Sante.EMR.SmartClient.OutBox.DSA.DataService
{
    // Generated code for the web service.
    // Use this proxy to make requests to the service when working in an application that is occasionally connected
    public class Callback : CallbackBase
    {
        public static event EventHandler<EventArgs<Form[]>> SendFormsReturn;
        public static event EventHandler<EventArgs<Exception>> SendFormsException;
		
        public override void OnSendFormsReturn(Request request, object[] parameters, Form[] returnValue)
        {
            if (SendFormsReturn != null)
            {
                SendFormsReturn(this, new EventArgs<Form[]>(returnValue));
            }
        }

		
        #region HelloWorld

        public override void OnHelloWorldReturn(Request request, object[] parameters, String returnValue)
        {
            throw new NotImplementedException();
        }

        public override OnExceptionAction OnHelloWorldException(Request request, Exception ex)
        {
            throw new NotImplementedException("Not implemented", ex);
        }

        #endregion HelloWorld

        #region HelloWorldAsync

        public override void OnHelloWorldAsync1Return(Request request, object[] parameters)
        {
            throw new NotImplementedException();
        }

        public override OnExceptionAction OnHelloWorldAsync1Exception(Request request, Exception ex)
        {
            throw new NotImplementedException("Not implemented", ex);
        }

        #endregion HelloWorldAsync

        #region HelloWorldAsync

        public override void OnHelloWorldAsync2Return(Request request, object[] parameters)
        {
            throw new NotImplementedException();
        }

        public override OnExceptionAction OnHelloWorldAsync2Exception(Request request, Exception ex)
        {
            throw new NotImplementedException("Not implemented", ex);
        }

        #endregion HelloWorldAsync

        #region GetModifiedTables

        public override void OnGetModifiedTablesReturn(Request request, object[] parameters, String[] returnValue)
        {
            throw new NotImplementedException();
        }

        public override OnExceptionAction OnGetModifiedTablesException(Request request, Exception ex)
        {
            throw new NotImplementedException("Not implemented", ex);
        }

        #endregion GetModifiedTables

        #region GetModifiedTablesAsync

        public override void OnGetModifiedTablesAsync1Return(Request request, object[] parameters)
        {
            throw new NotImplementedException();
        }

        public override OnExceptionAction OnGetModifiedTablesAsync1Exception(Request request, Exception ex)
        {
            throw new NotImplementedException("Not implemented", ex);
        }

        #endregion GetModifiedTablesAsync

        #region GetModifiedTablesAsync

        public override void OnGetModifiedTablesAsync2Return(Request request, object[] parameters)
        {
            throw new NotImplementedException();
        }

        public override OnExceptionAction OnGetModifiedTablesAsync2Exception(Request request, Exception ex)
        {
            throw new NotImplementedException("Not implemented", ex);
        }

        #endregion GetModifiedTablesAsync

        #region Search

        public override void OnSearchReturn(Request request, object[] parameters, Patient[] returnValue)
        {
            throw new NotImplementedException();
        }

        public override OnExceptionAction OnSearchException(Request request, Exception ex)
        {
            throw new NotImplementedException("Not implemented", ex);
        }

        #endregion Search

        #region SearchAsync

        public override void OnSearchAsync1Return(Request request, object[] parameters)
        {
            throw new NotImplementedException();
        }

        public override OnExceptionAction OnSearchAsync1Exception(Request request, Exception ex)
        {
            throw new NotImplementedException("Not implemented", ex);
        }

        #endregion SearchAsync

        #region SearchAsync

        public override void OnSearchAsync2Return(Request request, object[] parameters)
        {
            throw new NotImplementedException();
        }

        public override OnExceptionAction OnSearchAsync2Exception(Request request, Exception ex)
        {
            throw new NotImplementedException("Not implemented", ex);
        }

        #endregion SearchAsync

        #region GetTasks

        public override void OnGetTasksReturn(Request request, object[] parameters, NextTask[] returnValue)
        {
            throw new NotImplementedException();
        }

        public override OnExceptionAction OnGetTasksException(Request request, Exception ex)
        {
            throw new NotImplementedException("Not implemented", ex);
        }

        #endregion GetTasks

        #region GetTasksAsync

        public override void OnGetTasksAsync1Return(Request request, object[] parameters)
        {
            throw new NotImplementedException();
        }

        public override OnExceptionAction OnGetTasksAsync1Exception(Request request, Exception ex)
        {
            throw new NotImplementedException("Not implemented", ex);
        }

        #endregion GetTasksAsync

        #region GetTasksAsync

        public override void OnGetTasksAsync2Return(Request request, object[] parameters)
        {
            throw new NotImplementedException();
        }

        public override OnExceptionAction OnGetTasksAsync2Exception(Request request, Exception ex)
        {
            throw new NotImplementedException("Not implemented", ex);
        }

        #endregion GetTasksAsync

        #region GetOPSCases

        public override void OnGetOPSCasesReturn(Request request, object[] parameters, Case[] returnValue)
        {
            throw new NotImplementedException();
        }

        public override OnExceptionAction OnGetOPSCasesException(Request request, Exception ex)
        {
            throw new NotImplementedException("Not implemented", ex);
        }

        #endregion GetOPSCases

        #region GetOPSCasesAsync

        public override void OnGetOPSCasesAsync1Return(Request request, object[] parameters)
        {
            throw new NotImplementedException();
        }

        public override OnExceptionAction OnGetOPSCasesAsync1Exception(Request request, Exception ex)
        {
            throw new NotImplementedException("Not implemented", ex);
        }

        #endregion GetOPSCasesAsync

        #region GetOPSCasesAsync

        public override void OnGetOPSCasesAsync2Return(Request request, object[] parameters)
        {
            throw new NotImplementedException();
        }

        public override OnExceptionAction OnGetOPSCasesAsync2Exception(Request request, Exception ex)
        {
            throw new NotImplementedException("Not implemented", ex);
        }

        #endregion GetOPSCasesAsync

        #region RetrieveMCTCases

        public override void OnRetrieveMCTCasesReturn(Request request, object[] parameters, Boolean returnValue)
        {
            throw new NotImplementedException();
        }

        public override OnExceptionAction OnRetrieveMCTCasesException(Request request, Exception ex)
        {
            throw new NotImplementedException("Not implemented", ex);
        }

        #endregion RetrieveMCTCases

        #region RetrieveMCTCasesAsync

        public override void OnRetrieveMCTCasesAsync1Return(Request request, object[] parameters)
        {
            throw new NotImplementedException();
        }

        public override OnExceptionAction OnRetrieveMCTCasesAsync1Exception(Request request, Exception ex)
        {
            throw new NotImplementedException("Not implemented", ex);
        }

        #endregion RetrieveMCTCasesAsync

        #region RetrieveMCTCasesAsync

        public override void OnRetrieveMCTCasesAsync2Return(Request request, object[] parameters)
        {
            throw new NotImplementedException();
        }

        public override OnExceptionAction OnRetrieveMCTCasesAsync2Exception(Request request, Exception ex)
        {
            throw new NotImplementedException("Not implemented", ex);
        }

        #endregion RetrieveMCTCasesAsync

        #region RetrieveIHITCases

        public override void OnRetrieveIHITCasesReturn(Request request, object[] parameters, Boolean returnValue)
        {
            throw new NotImplementedException();
        }

        public override OnExceptionAction OnRetrieveIHITCasesException(Request request, Exception ex)
        {
            throw new NotImplementedException("Not implemented", ex);
        }

        #endregion RetrieveIHITCases

        #region RetrieveIHITCasesAsync

        public override void OnRetrieveIHITCasesAsync1Return(Request request, object[] parameters)
        {
            throw new NotImplementedException();
        }

        public override OnExceptionAction OnRetrieveIHITCasesAsync1Exception(Request request, Exception ex)
        {
            throw new NotImplementedException("Not implemented", ex);
        }

        #endregion RetrieveIHITCasesAsync

        #region RetrieveIHITCasesAsync

        public override void OnRetrieveIHITCasesAsync2Return(Request request, object[] parameters)
        {
            throw new NotImplementedException();
        }

        public override OnExceptionAction OnRetrieveIHITCasesAsync2Exception(Request request, Exception ex)
        {
            throw new NotImplementedException("Not implemented", ex);
        }

        #endregion RetrieveIHITCasesAsync

        #region CancelAsync

        public override void OnCancelAsyncReturn(Request request, object[] parameters)
        {
            throw new NotImplementedException();
        }

        public override OnExceptionAction OnCancelAsyncException(Request request, Exception ex)
        {
            throw new NotImplementedException("Not implemented", ex);
        }

        #endregion CancelAsync

    }
}
